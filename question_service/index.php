<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
LocalRedirect('/');
$APPLICATION->SetTitle("Часто задаваемые вопросы");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
global $my_city;
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<link rel="stylesheet" href="/question_service/questionstyle19.css?<?=$release?>" type="text/css"/>
<div class="e-new-cont e-new-header-offset">

<section class="e-breadcrumbs">
    <ul class="e-b-items">
        <li class="e-b-item home">
            <a href="/" title="На главную">
                <span>главная</span>
            </a>
        </li>
        <li class="e-b-item bread-elem">
        <span title="Часто задаваемые вопросы">
            <span>часто задаваемые вопросы</span>
        </span>
        </li>
    </ul>
</section>
<?
$iblock_res = CIBlock::GetList(Array(), Array('CODE' => 'faq'));
while($iblock_res_arr = $iblock_res->Fetch()) $iblock_id_faq = $iblock_res_arr["ID"];

$tags_arr = Array();
$property_enums = CIBlockPropertyEnum::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id_faq, "CODE"=>"FAQ_TAGS"));
while($enum_fields = $property_enums->GetNext()) {
  //print_r($enum_fields);
  //echo "<br>";
    $tags_arr[$enum_fields["ID"]]['val'] = $enum_fields["VALUE"];
    $tags_arr[$enum_fields["ID"]]['qty'] = 0;
    $tags_arr[$enum_fields["ID"]]['xml_id'] = $enum_fields["XML_ID"];
}
//print_r($tags_arr);

$faq_arr = Array();
$res = CIBlockElement::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $iblock_id_faq, "ACTIVE" => "Y"), false, Array(), Array());
while($ob = $res->GetNextElement()) {
    $item = array_merge($ob->GetFields(), $ob->GetProperties());
    $faq_arr[] = $item;
    $tags_arr[$item['FAQ_TAGS']['VALUE_ENUM_ID']]['qty'] = $tags_arr[$item['FAQ_TAGS']['VALUE_ENUM_ID']]['qty'] + 1;
}
//print_r($faq_arr);
?>
<section class="faq">
    <div class="left-part">
        <h1><i class="new-icomoon icon-help"></i><span>Часто задаваемые вопросы</span></h1>
        <div class="faq-desc">Сегодня мы ответили более чем&nbsp;на&nbsp;10&nbsp;000 вопросов <br>в&nbsp;техподдержку по&nbsp;разным направлениям:</div>
        <div class="faq-tags-wrap">
          <?
            foreach($tags_arr as $t => $tag) {
              if($tag['qty'] != 0) { ?>
                <div class="faq-tag<?if($tag['xml_id'] == 'mounting' || $tag['xml_id'] == 'catalogues') echo ' faq-tag-two'?>" data-val="<?=$t?>"><?=$tag['val']?> <span><?=$tag['qty']?></span></div>
          <? }
            } ?>
        </div>
      <div class="faq-search-wrap">
        <form>
          <input type="text" placeholder="Поиск" name="faq">
          <button type="button" data-type="search-faq"><i class="new-icomoon icon-search"></i></button>
        </form>
      </div>

       <div class="faq-answer-wrap">
         <? $n = 1; ?>
         <? foreach($faq_arr as $item) { ?>
          <div class="faq-answer-item">
                <div class="faq-answer-top">
                    <div class="faq-answer-top-nmbr">Вопрос</div>
                    <div class="faq-answer-top-tags">
                        <div class="faq-answer-tag<?if($item['FAQ_TAGS']['VALUE_XML_ID'] == 'mounting' || $item['FAQ_TAGS']['VALUE_XML_ID'] == 'catalogues') echo ' faq-answer-tag-two'?>"><?=$item['FAQ_TAGS']['VALUE']?></div>
                    </div>
                </div>
                <div class="faq-answer-bottom">
                    <h2><?=$item['~NAME']?></h2>
                    <div class="faq-answer">
                        <p><?=$item['~DETAIL_TEXT']?></p>
                    </div>
                    <div class="faq-answer-show">
                        <span>Показать ответ</span> <i class="new-icomoon icon-Angle-down"></i>
                    </div>
                </div>
            </div>
        <? } ?>
        </div>
    </div>




    <div class="right-part">
      <div class="right-part-form">
        <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
        <div class="viewport">
          <div class="overview">
            <h3>Свой вопрос <br>вы&nbsp;можете задать ниже:</h3>
            <form class="aqs-main-form question-form" data-type="aqs-main-form" id="faq-aqsMainForm">
              <input type="hidden" name="aqs-city" value="<?=$my_city?>">
              <input type="hidden" name="aqs-page" value="" id="faq-e-aqs-input-page">
              <label for="faq-aqs-name">Ваше имя*</label>
              <input type="text" name="aqs-name" id="faq-aqs-name">
              <label for="faq-aqs-tel">Контактный телефон</label>
              <input type="text" name="aqs-tel" id="faq-aqs-tel">
              <label for="faq-aqs-email">E-mail*</label>
              <input type="text" name="aqs-email" id="faq-aqs-email">
              <label for="faq-aqs-loc">Укажите город, в котором вы находитесь*</label>
              <input type="text" name="aqs-loc" id="faq-aqs-loc">
              <div class="e-aqs-select-wrap">
                <select name="aqs-subj" data-placeholder="Выберите тему вопроса*" data-type="q-form-select">
                  <option value="" disabled selected style="display:none;"></option>
                  <option value="2">Монтаж изделий</option>
                  <option value="3">Свойства изделий</option>
                  <option value="4">Претензии и&nbsp;вопросы по&nbsp;заказам и&nbsp;сервису</option>
                  <option value="1">Ассортимент и&nbsp;уточнение по&nbsp;размерам</option>
                  <option value="5">Гарантийные обязательства</option>
                  <option value="6">Работа магазинов</option>
                  <option value="7">Другое</option>
                </select>
              </div>
              <div class="e-aqs-file-wrapper">
                <label>
                  <i class="br-icon-paper-clip"></i>
                  <span class="e-aqs-file-lbl">Прикрепить файл</span>
                  <span class="e-aqs-file-name" data-type="add-file"></span>
                  <input type="file" name="aqs-file" accept="image/jpeg,image/png,.zip,.rar,.pdf">
                  <span class="e-aqs-file-browse">Обзор</span>
                </label>
              </div>
              <p class="e-aqs-file-exmpl">
                Разрешены к отправке файлы: изображения JPEG, PNG, PDF,
                архивы RAR, ZIP, размер файла не должен превышать 10 МБ
              </p>
              <label for="faq-aqs-qst">Ваш вопрос*:</label>
              <textarea name="aqs-qst" id="faq-aqs-qst"></textarea>
              <input type="checkbox" id="faq-aqs_policy" name="aqs_policy" class="q-check">
              <label for="faq-aqs_policy" class="aqs_policy_label">Я согласен(на) <span>на&nbsp;обработку <a href="/company/policies" target="_blanc">персональных данных</a></span></label>
              <div class="e-aqs-form-loader"><img src="/images/AjaxLoader.gif" alt=""></div>
              <div class="e-aqs-form-confirm" data-type="aqs-rqst"></div>
              <button type="button" class="e-aqs-form-button">Отправить</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</section>

</div>

<?require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && "B_PROLOG_INCLUDED"===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}