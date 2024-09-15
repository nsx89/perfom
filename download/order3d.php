<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Вопрос по 3D модели");
?>
<?
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}
//заглушка для картинок
$blockid = 3;
$section_hash = array();
$arFilter = Array('IBLOCK_ID' => $blockid, 'ACTIVE' => 'Y');
$db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array('UF_*'));
while ($s = $db_list->GetNext()) {
    $section_hash[$s['ID']] = $s['UF_OLDID'];
}
require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/header.php");

global $my_city; // регион
$loc = null;
    $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'ID' => $my_city);
    $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
    $loc = $db_list->GetNextElement();
    if (!$loc) {
        $arFilter = Array('IBLOCK_ID' => 7, 'ACTIVE' => 'Y', 'CODE' => 'moskva');
        $db_list = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter);
        $loc = $db_list->GetNextElement();
    }
    $loc = array_merge($loc->GetFields(), $loc->GetProperties());
 


$errors = array();
if (!$_POST['articul']) {
    $errors['articul'] = "*";
}
if (!$_POST['fio']) {
    $errors['fio'] = "*";
}
if (!$_POST['email']) {
    $errors['email'] = "*";
}
if (!$_POST['region']) {
    $errors['region'] = "*";
}
if (!$_POST['comment']) {
    $errors['comment'] = "*";
}
if (count($errors)) {
    $_POST['action'] = 0;
}


?>
<script type="text/javascript"><!--

$(document).ready(function() { 
    $("#create_order_form").submit(function(){ // перехватываем все при событии отправки
        var form = $(this); // запишем форму, чтобы потом не было проблем с this
        var error = false; 
	if ($('#articul').val() == '') {
		alert('Заполните поле < Перечень артикулов > !');
                error = true;
	} 
        if ($('#fio').val() == '') {
		alert('Заполните поле < Ваше имя > !');
                error = true;
	}
	if ($('#email').val() == '') {
		alert('Заполните поле < Email > !');
                error = true;
	} 
	else {
		var regCheck = new RegExp("^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$");
		if (!regCheck.test($('#email').val())) {
		alert('Не верный формат поля < Email > !');
                error = true;
		}
	}
	if ($('#cur_region').val() == '') {
		alert('Заполните поле < Регион > !');
                error = true;
	}
	if ($('#comment').val() == '') {
		alert('Заполните < Ваш вопрос > !');
                error = true;
	}
    if ($('#m_policy').attr("checked") != "checked") {
        alert('Укажите, что Вы согласны на обработку персональных данных!');
                error = true;
    }

	if (!error) { return true; }// вырубаем стандартную отправку формы
	else { return false; }
 });
});
--></script>

<?if (!isset($_POST['action']) || $_POST['action'] != 1) {?>
    <div id="middle">
        <div id="cart_wt">
            <div class="block_t_2">
                <div class="bt2_header">
                    <span>Вопрос по 3D модели</span>
                </div>
                <div class="bt2_body" id="cart_list">
                    <form action="" method="post" id="create_order_form">
                        <input type="hidden" name="action" value="1">
                        <table class="order-contact">
			    <tr>
				
                                <td>Перечень артикулов</td>
                                <td><input name="articul" type="text" id="articul" style="width: 400px;" placeholder="Введите перечень артикулов через запятую">
                                    <span class="order-contact-field-error" style="padding-left: 5px;""><?=isset($errors['articul'])?$errors['articul']:''?></span>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Ваше имя</td>
                                <td><input name="fio" type="text" id="fio" style="width: 400px;" placeholder="Ваше имя">
                                    <span class="order-contact-field-error" style="padding-left: 5px;"><?=isset($errors['fio'])?$errors['fio']:''?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input name="email" type="text" id="email" style="width: 400px;" placeholder="email - для обратной связи">
                                    <span class="order-contact-field-error" style="padding-left: 5px;" ><?=isset($errors['email'])?$errors['email']:''?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Телефон</td>
                                <td><input name="phone" type="text" style="width: 400px; " placeholder="телефон">
                                    <span class="order-contact-field-error" style="padding-left: 5px;"><?=isset($errors['phone'])?$errors['phone']:''?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Регион</td>
                                <td><input name="region" type="text" id="cur_region" style="width: 400px;">
				 
					<script type="text/javascript">
					var obj=document.getElementById("cur_region");
					obj.value="<? echo $loc['NAME'] ?>";
					</script>
				
				    <span class="order-contact-field-error" style="padding-left: 5px;"><?=isset($errors['region'])?$errors['region']:''?></span>
				</td>
                            </tr>
			    <tr>
                                <td>Ваш вопрос</td>
                                <td><textarea name="comment" id="comment" cols="48" placeholder="Ваш вопрос"></textarea>
				<span class="order-contact-field-error" style="padding-left: 5px;"><?=isset($errors['comment'])?$errors['comment']:''?></span>
				
				</td>
                            </tr>
                <tr class="tr-policy">
                    <td colspan="2" style="padding-top:10px;padding-bottom:15px;"><input type="checkbox" name="m_policу" id="m_policy"><label for="m_policy" class="m_policy_label">Я согласен на <a href="/about/policies" target="_blanc">обработку персональных данных</a><span class="order-contact-field-error" style="padding-left: 5px;">*</span></label></td>
                </tr>
			      <td></td>
                              <td><span class="order-contact-field-error" style="padding-left: 15px; padding-rigth: 5px; color: #4F4F4F;"> помеченное </span>
			      <span class="order-contact-field-error" style="padding-left: 5px; padding-rigth: 5px;"> * </span>
			      <span class="order-contact-field-error" style="padding-left: 5px; padding-rigth: 5px; color: #4F4F4F;"> - обязательно к заполнению </span>
			     </td>
                            </tr>

			</table>

                        <input type="submit" value="Отправить запрос" onClick="send3d()">

			 <span class="order-contact-field-error" style="padding-left: 30px; padding-rigth: 0px; color: #000000;">Согласны ли вы получать обновления базы данных моделей? &nbsp&nbsp&nbsp&nbsp</span>

			<input name="update3D"  type="radio" id="update3D_on" value="Да" checked="checked"><label for='update3D_on'>&nbspДа&nbsp&nbsp</label>
			<input name="update3D"  type="radio" id="update3D_off" value="Нет" ><label for='update3D_off'>&nbspНет</label>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
<?} elseif (isset($_POST['action']) || $_POST['action'] == 1) {?>

    <?
    $pr_list = "Перечень артикулов: ".$_POST['articul'];
    $dealer = "<br>Вопрос по 3D модели";
    $from = "<br><b>Согласие на получение обновлений базы - ".$_POST['update3D']."</b><br><br>";
    $from .= "<br>Email: ".$_POST['email']."<br><br>";
    $from .= "Клиент: ".$_POST['fio'].", тел: ".$_POST['phone']."<br><br>";
    $from .= "Регион: ".$_POST['region']."<br><br>";
    $from .= "Ваш вопрос: ".$_POST['comment'];
    $fields = array('EMAIL'=>$_POST['email'], 'PRODUCT_LIST'=>$pr_list, 'DEALER_INFO'=>$dealer,'CLIENT'=>$from);
    CEvent::SendImmediate("EUROPLAST_ORDER_3D", s1, $fields, "N", 68);



    $fields = array('EMAIL'=>'o.gmirya@decor-evroplast.ru', 'PRODUCT_LIST'=>$pr_list, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from);
    CEvent::SendImmediate("EUROPLAST_ORDER_3D", s1, $fields, "N", 68);
    
    $fields = array('EMAIL'=>'d.portu.by@yandex.ru', 'PRODUCT_LIST'=>$pr_list, 'DEALER_INFO'=>$dealer, 'CLIENT'=>$from);
    CEvent::SendImmediate("EUROPLAST_ORDER_3D", s1, $fields, "N", 68);




    //EUROPLAST_ORDER
    //#PRODUCT_LIST#
    //#DEALER_INFO#

    ?>


    <div id="middle">
        <div id="cart_wt">
            <div class="block_t_2">
                <div class="bt2_header">
                    <span>Оформление вопроса по 3D модели</span>
                </div>
                <div class="bt2_body" id="cart_list">
                    Вопрос отправлен. В ближайшее время с Вами свяжутся по телефону или e-mail.
                </div>
            </div>
        </div>
    </div>
<?}?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/footer.php"); ?>
